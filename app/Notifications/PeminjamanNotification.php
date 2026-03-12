<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PeminjamanNotification extends Notification
{
    use Queueable;

    public Peminjaman $peminjaman;
    public $action;
    public $message;

    public function __construct(Peminjaman $peminjaman, string $action)
    {
        $this->peminjaman = $peminjaman;
        $this->action = $action;
        
        switch ($action) {
            case 'created':
                $this->message = "Peminjaman baru oleh {$peminjaman->peminjam->name} untuk {$peminjaman->alat->nama}";
                break;
            case 'dipinjam':
                $this->message = "Peminjaman {$peminjaman->kode} telah disetujui dan sedang dipinjam";
                break;
            case 'rejected':
                $this->message = "Peminjaman {$peminjaman->kode} telah ditolak";
                break;
            case 'returned':
                $this->message = "Peminjaman {$peminjaman->kode} telah dikembalikan";
                break;
            default:
                $this->message = "Update peminjaman {$peminjaman->kode}";
        }
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject("Update Peminjaman [{$this->peminjaman->kode}] - {$this->action}")
            ->greeting("Halo {$notifiable->name},")
            ->line($this->message)
            ->line("Berikut adalah rincian data peminjaman Anda:")
            ->line("**Kode Peminjaman:** {$this->peminjaman->kode}")
            ->line("**Alat:** {$this->peminjaman->alat->nama}")
            ->line("**Tanggal Pinjam:** " . \Carbon\Carbon::parse($this->peminjaman->tanggal_pinjam)->format('d M Y H:i'))
            ->line("**Estimasi Kembali:** " . ($this->peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($this->peminjaman->tanggal_kembali)->format('d M Y') : '-'));

        if ($this->action === 'dipinjam') {
            $mailMessage->line("Silakan unduh **Resi Peminjaman** yang terlampir pada email ini sebagai bukti pengambilan alat.")
                ->action('Buka Dashboard', url('/dashboard'));
            
            $qrcode = QrCode::size(200)->generate($this->peminjaman->kode);
            
            $pdf = Pdf::loadView('peminjaman.resi', [
                'peminjaman' => $this->peminjaman,
                'qrcode' => $qrcode
            ]);
            
            $mailMessage->attachData($pdf->output(), "Resi-Peminjaman-{$this->peminjaman->kode}.pdf", [
                'mime' => 'application/pdf',
            ]);
        } else {
            $mailMessage->action('Lihat Detail', url('/dashboard/transaksi/peminjaman/' . $this->peminjaman->id));
        }

        $mailMessage->line('Terima kasih telah menggunakan layanan SIPINJAM!');

        return $mailMessage;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'peminjaman',
            'action' => $this->action,
            'message' => $this->message,
            'peminjaman_id' => $this->peminjaman->id,
            'kode_peminjaman' => $this->peminjaman->kode,
            'user_name' => $this->peminjaman->peminjam->name ?? 'Unknown',
            'alat_name' => $this->peminjaman->alat->nama ?? 'Unknown',
        ];
    }
}

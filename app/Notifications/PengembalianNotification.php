<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pengembalian;

class PengembalianNotification extends Notification
{
    use Queueable;

    public $pengembalian;
    public $action;
    public $message;

    public function __construct(Pengembalian $pengembalian, string $action)
    {
        $this->pengembalian = $pengembalian;
        $this->action = $action;
        
        switch ($action) {
            case 'created_manual':
                $this->message = "Pengembalian (Manual) oleh {$pengembalian->peminjaman->peminjam->name} untuk {$pengembalian->peminjaman->alat->nama}";
                break;
            case 'created_scan':
                $this->message = "Pengembalian (Scan QR) oleh {$pengembalian->peminjaman->peminjam->name} untuk {$pengembalian->peminjaman->alat->nama}";
                break;
            case 'created':
                $this->message = "Pengembalian baru oleh {$pengembalian->peminjaman->peminjam->name} untuk {$pengembalian->peminjaman->alat->nama}";
                break;
            case 'approved':
                $this->message = "Pengembalian {$pengembalian->peminjaman->kode} telah disetujui";
                break;
            case 'rejected':
                $this->message = "Pengembalian {$pengembalian->peminjaman->kode} telah ditolak";
                break;
            default:
                $this->message = "Update pengembalian {$pengembalian->peminjaman->kode}";
        }
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Konfirmasi Pengembalian [{$this->pengembalian->peminjaman->kode}] - {$this->action}")
            ->greeting("Halo {$notifiable->name},")
            ->line($this->message)
            ->line("Berikut adalah rincian data pengembalian:")
            ->line("**Kode Peminjaman:** {$this->pengembalian->peminjaman->kode}")
            ->line("**Alat:** {$this->pengembalian->peminjaman->alat->nama}")
            ->line("**Tanggal Kembali:** " . \Carbon\Carbon::parse($this->pengembalian->tanggal_dikembalikan)->format('d M Y H:i'))
            ->line("**Kondisi Barang:** " . strtoupper($this->pengembalian->kondisi_kembali))
            ->line("**Catatan:** " . ($this->pengembalian->catatan ?? '-'))
            ->action('Lihat Detail', url('/dashboard/transaksi/pengembalian/' . $this->pengembalian->id))
            ->line('Terima kasih telah mengembalikan alat tepat waktu!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'pengembalian',
            'action' => $this->action,
            'message' => $this->message,
            'pengembalian_id' => $this->pengembalian->id,
            'kode_pengembalian' => $this->pengembalian->peminjaman->kode,
            'user_name' => $this->pengembalian->peminjaman->peminjam->name ?? 'Unknown',
            'alat_name' => $this->pengembalian->peminjaman->alat->nama ?? 'Unknown',
        ];
    }
}

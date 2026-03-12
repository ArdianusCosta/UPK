<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Alat;

class AlatNotification extends Notification
{
    use Queueable;

    public Alat $alat;
    public $action;
    public $message;

    public function __construct(Alat $alat, string $action)
    {
        $this->alat = $alat;
        $this->action = $action;
        
        switch ($action) {
            case 'created':
                $this->message = "Alat baru ditambahkan: {$alat->nama}";
                break;
            case 'updated':
                $this->message = "Alat diperbarui: {$alat->nama}";
                break;
            case 'deleted':
                $this->message = "Alat dihapus: {$alat->nama}";
                break;
            case 'low_stock':
                $this->message = "Stok alat {$alat->nama} menipis ({$alat->stok} tersisa)";
                break;
            default:
                $this->message = "Update alat: {$alat->nama}";
        }
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Informasi Inventaris Alat - {$this->action}")
            ->greeting("Halo {$notifiable->name},")
            ->line($this->message)
            ->line("Berikut adalah rincian alat:")
            ->line("**Nama Alat:** " . ($this->alat->nama ?? 'Unknown'))
            ->line("**Kategori:** " . ($this->alat->kategoriAlat->nama_kategori_alat ?? 'Uncategorized'))
            ->line("**Stok Saat Ini:** " . ($this->alat->stok ?? 0))
            ->line("**Status Alat:** " . strtoupper($this->alat->status ?? 'unknown'))
            ->action('Manajemen Alat', url('/dashboard/master-data/alat'))
            ->line('Terima kasih!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'alat',
            'action' => $this->action,
            'message' => $this->message,
            'alat_id' => $this->alat->id,
            'nama_alat' => $this->alat->nama ?? 'Unknown',
            'kategori' => $this->alat->kategoriAlat->nama_kategori ?? 'Uncategorized',
            'stok' => $this->alat->stok ?? 0,
            'status' => $this->alat->status ?? 'unknown',
        ];
    }
}

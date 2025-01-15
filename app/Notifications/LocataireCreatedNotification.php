<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LocataireCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $email;
    public $password;
    public $proprietaire;

    /**
     * Create a new notification instance.
     */
    public function __construct($email, $password, $proprietaire)
    {
        $this->email = $email;
        $this->password = $password;
        $this->proprietaire = $proprietaire;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre compte a été créé - Gestion Immobilière - NZOapp')
            ->greeting('Bonjour ' . $notifiable->nom)
            ->line('Votre compte a été créé avec succès par votre propriétaire, ' . $this->proprietaire->nom . '.')
            ->line('Voici vos informations de connexion :')
            ->line('Email : ' . $this->email)
            ->line('Mot de passe : ' . $this->password)
            ->line('Vous pouvez télécharger l’application pour gérer vos interactions.')
            ->action('Télécharger l’App', url('https://www.apple.com/app-store/')) // Lien temporaire pour l’App Store
            ->line('Merci de faire partie de notre communauté.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

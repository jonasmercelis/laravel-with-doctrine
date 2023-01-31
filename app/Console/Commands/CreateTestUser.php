<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Entities\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Routing\UrlGenerator;

final class CreateTestUser extends Command
{
    private const EMAIL_ARG = 'email';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email?} {--password=123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a basic test user for the application and persists it in the database.';

    /**
     * Execute the console command.
     *
     * @param EntityManagerInterface $em
     * @param Hasher $hasher
     * @param UrlGenerator $urlGenerator
     * @return int
     */
    public function handle(EntityManagerInterface $em, Hasher $hasher, UrlGenerator $urlGenerator): int
    {
        // Retrieve email from command argument.
        $email = $this->argument(self::EMAIL_ARG) ?? 'test@test.com';
        $password = $this->option('password');

        // Create new user.
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($hasher->make($password));

        // Try to persist user
        try {

            $em->persist($user);
            $em->flush($user);
        } catch (UniqueConstraintViolationException $exception) {

            $this->warn("User has not been created: $email already exists.");
            $this->warn($exception->getMessage());
            return \Symfony\Component\Console\Command\Command::FAILURE;
        }

        // Get the login URL.
        $loginUrl = $urlGenerator->route('login');
        $this->info("User $email has been created. You can now login as that user at $loginUrl");
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

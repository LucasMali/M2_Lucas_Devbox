<?php

namespace Lucas\Devbox\Command;

use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Encryption\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Encryption extends Command
{
    const PASSWORD = 'password';
    const HASH = 'hash';
    const SALT = 'salt';

    /**
     * @var Encryptor
     */
    private $e;

    public function __construct(
        Encryptor $e,
        AccountManagement $am,
        string $name = null
    ) {
        $this->e = $e;
        parent::__construct($name);
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::PASSWORD,
                'p',
                InputOption::VALUE_REQUIRED,
                'Password'
            ),
            new InputOption(
                self::HASH,
                'ha',
                InputOption::VALUE_OPTIONAL,
                'Hash'
            ),
            new InputOption(
                self::SALT,
                's',
                InputOption::VALUE_OPTIONAL,
                'Salt'
            )
        ];

        $this->setName("z:encrypt");
        $this->setDescription("A command the programmer was too lazy to enter a description for.");
        $this->setDefinition($options);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $_version = 2;
        $_pass = $input->getOption(self::PASSWORD);
        $_hash = $input->getOption(self::HASH) ?? "You forgot to give the command some info";
        $_salt = $input->getOption(self::SALT);

        if (
            strpos($_hash, ':')
        ) {
            list($_hash, $_salt, $_version) = explode(':', $_hash);
        }

        if ($_pass && $_salt) {
            $_hash = $this->e->getHash($_pass, $_salt, (int)$_version);
        }

        $output->writeln($_hash);
    }
}

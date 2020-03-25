<?php

namespace Lucas\Devbox\Command;

use Magento\Customer\Model\AccountManagement;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException as LocalizedExceptionAlias;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetPassword extends Command
{
    const EMAIL = 'email';

    /**
     * @var AccountManagement
     */
    private $am;
    /**
     * @var State
     */
    private $state;

    public function __construct(
        AccountManagement $am,
        State $state,
        string $name = null
    ) {
        $this->am = $am;
        $this->state = $state;
        parent::__construct($name);
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::EMAIL,
                'e',
                InputOption::VALUE_REQUIRED,
                'Email'
            )
        ];

        $this->setName("z:reset:password");
        $this->setDescription("Init password reset the password");
        $this->setDefinition($options);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($_email = $input->getOption(self::EMAIL)) {
            try {
                $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
                $_message = $this->am->initiatePasswordReset($_email, AccountManagement::EMAIL_RESET, 1);
            } catch (LocalizedExceptionAlias $e) {
                $_message = $e->getMessage();
            }
            $output->writeln($_message);
        }
    }
}

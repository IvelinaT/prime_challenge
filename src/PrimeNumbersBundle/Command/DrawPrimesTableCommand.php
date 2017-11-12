<?php

namespace PrimeNumbersBundle\Command;

use PrimeNumbersBundle\CMD\PrimeTableInterface;
use PrimeNumbersBundle\CMD\PrimeTableArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DrawPrimesTableCommand extends ContainerAwareCommand implements PrimeTableInterface
{
    protected function configure()
    {
        $this
            ->setName(self::CMD_NAME)
            ->setDescription(self::CMD_DESCRIPTION)
            ->setDefinition(
                new InputDefinition(
                    array(
                        new InputArgument(self::FIRST, InputArgument::OPTIONAL, 'starting number', 0),
                        new InputArgument(self::LIMIT, InputArgument::OPTIONAL, 'count of prime numbers to be displayed.', 10),
                    )
                )
            )
            ->setHelp(
                sprintf(
                    'Set starting number with first argument, default is 0.'
                    .'If you\'d like to generate table with N numbers, set second argument (limit), default is 10.'
                    ,
                    self::FIRST,
                    self::LIMIT
                )
            );

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = new PrimeTableArgument();
        $arguments->setFirst($input->getArgument(self::FIRST));
        $arguments->setLimit($input->getArgument(self::LIMIT));
        $this->validateInputValues($arguments);
        $this->getPrimeNumbersService()->setPrimeNumbersArguments($arguments);
        $this->getTableCellValueService()->renderTable($output);
    }

    /**
     * @param PrimeTableArgument $arguments
     * @return bool
     */
    protected function validateInputValues(PrimeTableArgument $arguments)
    {
        if (!is_numeric($arguments->getFirst()) or !is_numeric($arguments->getLimit())) {
            throw new \InvalidArgumentException('Both arguments should be integers.');
        }
        if ($arguments->getLimit() < 1) {
            throw new \InvalidArgumentException('Prime Numbers limit should be more than 0');
        }
        if ($arguments->getLimit() > self::MAX_NUMBER) {
            throw new \InvalidArgumentException(sprintf('Prime Numbers limit can not exceed %d',self::MAX_NUMBER));
        }

        return true;
    }

    private function getPrimeNumbersService()
    {
        return $this->getContainer()->get('prime_numbers.prime_numbers_service');
    }

    private function getTableCellValueService()
    {
        return $this->getContainer()->get('prime_numbers.prime_numbers_table');
    }
}

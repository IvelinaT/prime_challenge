<?php
namespace PrimeNumbersBundle\Services;


use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class PrimeNumbersTableService
{
    /**
     * @var array
     */
    private $numbers;

    public function __construct($numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @var int
     * @var int
     * @return int
     */
    protected function getTableCellValue($row, $col)
    {
        if ($col == 0) {
            return $this->numbers[$row];
        }

        return $this->numbers[$row] * $this->numbers[$col - 1];
    }

    public function renderTable(OutputInterface $output)
    {
        $this->checkInputArguments();
        $table = new Table($output);
        $total = $this->getPrimeNumbersCount();
        $primeNumbers = array_merge(array('' => 'X'), $this->getNumbers());
        $table->setHeaders($primeNumbers);
        $rows = array();
        for ($col = 0, $row = 0; $col < $total - 1, $row < $total; $col++) {
            $rows[$row][$col] = $this->getTableCellValue($row, $col);
            if ($col >= $total) {
                $col = -1;
                $row++;
            }
        }
        $table->setRows($rows);

        return $table->render();
    }


    private function checkInputArguments()
    {
        if (empty($this->numbers)) {
            throw new InvalidNumbersException("Unable to find prime numbers");
        }
    }

    protected function getPrimeNumbersCount()
    {
       return count($this->numbers);
    }

    /**
     * @return array
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * @param array $numbers
     */
    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;
    }
}
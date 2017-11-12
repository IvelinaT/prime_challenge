Feature: Prime Table
  In order to display table with prime numbers
  Then I should see table with prime numbers

  Scenario: Test command
    When I execute command
    Then Command exit with success

  Scenario: Trying to display table with first 10 prime numbers
    Given I do not provide any parameters
    When I execute command
    Then Command exit with success

  Scenario: I cannot run command with invalid arguments.
    Given I execute command with arguments
      | first       | 4           |
      | limit       | '99'        |
    Then the command exception should be "InvalidArgumentException"

  Scenario Outline: Trying to display table with first N prime numbers
    Given I execute command with arguments
      | first    | 10    |
      | limit    | 12    |
    Then  I should get table with <multiplication_count> value
    Examples:
      | multiplication_count|
      |  144                  |

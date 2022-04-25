@ou @ou_vle @quizaccess @quizaccess_honestycheck @javascript
Feature: Test all the basic functionality of honesty check quiz access rule
  In order to stop students cheating
  As an teacher
  I need to make them promise to be good before starting a quiz.

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "users" exist:
      | username | firstname |
      | teacher  | Teachy    |
      | student  | Study     |
    And the following "course enrolments" exist:
      | user    | course | role           |
      | teacher | C1     | editingteacher |
      | student | C1     | student        |

  @javascript
  Scenario: Require students to agree, then check the they have to.
    # Add a quiz to a course without the condition, and verify that they can start it as normal.
    Given I am on the "Course 1" "Course" page logged in as "teacher"
    And I turn editing mode on
    And I add a "Quiz" to section "1" and I fill the form with:
      | Name        | Quiz no honesty check                                    |
      | Description | This quiz does not require students to agree to anything |
    And I add a "True/False" question to the "Quiz no honesty check" quiz with:
      | Question name                      | First question               |
      | Question text                      | Is this the second question? |
      | Correct answer                     | False                        |
    And I log out
    And I am on the "Quiz no honesty check" "mod_quiz > View" page logged in as "student"
    When I press "Attempt quiz"
    Then I should see "Question 1"

    # Add a quiz to a course with the condition, and verify that the student is challenged.
    When I log out
    Given I am on the "Course 1" "Course" page logged in as "teacher"
    And I turn editing mode on
    And I add a "Quiz" to section "1" and I fill the form with:
      | Name        | Quiz with honesty check                          |
      | Description | This quiz require students to agree not to cheat |
      | Students cognisance of plagiarism policy | must be acknowledged before starting an attempt   |
    And I add a "True/False" question to the "Quiz with honesty check" quiz with:
      | Question name                      | First question              |
      | Question text                      | Is this the first question? |
      | Correct answer                     | True                        |
    And I log out
    And I am on the "Quiz with honesty check" "mod_quiz > View" page logged in as "student"
    When I press "Attempt quiz"
    Then I should see "Please read the following message"
    And I should see "I understand that it is important that the attempt I am about to make is all my own work."

    # Continuing without ticking is blocked.
    And I click on "Start attempt" "button" in the "Start attempt" "dialogue"
    Then I should see "You must agree to this statement before you start the quiz."

    # Continuing with ticking is OK.
    When I set the field "I have read and agree to the above statement" to "1"
    And I press "Start attempt"
    Then I should see "Question 1"

    # Test that backup and restore keeps the setting.
    When I log out
    Given I am on the "Course 1" "Course" page logged in as "teacher"
    And I turn editing mode on
    And I duplicate "Quiz with honesty check" activity editing the new copy with:
      | Name | Duplicated quiz with honesty check |
   # And I follow "Duplicated quiz with honesty check"
    And I log out
    And I am on the "Duplicated quiz with honesty check" "mod_quiz > View" page logged in as "teacher"
    And I press "Preview quiz"
    Then I should see "Please read the following message"

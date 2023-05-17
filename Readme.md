# The Honesty check quiz access rule

If you install this plugin, there is a new option on the quiz settings form, and
if the teacher turns that on, then when a student tries to start a quiz attempt,
they will see a statement about plagiarism and cheating, and they will have to
agree that they will be good before they are allowed to start the quiz attempt.

## Installation

### Install from the plugins database

Install from the Moodle plugins database https://moodle.org/plugins/quizaccess_honestycheck
in the normal way.

### Install using git

Or you can install using git. Type these commands in the root of your Moodle install

    git clone https://github.com/moodleou/moodle-quizaccess_honestycheck.git mod/quiz/accessrule/honestycheck
    echo '/mod/quiz/accessrule/honestycheck/' >> .git/info/exclude

Then run the moodle update process Site administration > Notifications

### Changing the text of the message

This statement students must agree to is a Moodle language string, so it can be changed using the
standard [Language customisation](https://docs.moodle.org/en/Language_customisation) process.

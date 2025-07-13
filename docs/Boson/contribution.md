# Contribution Guide

<show-structure for="chapter" depth="2"/>

## Code of Conduct

The Boson code of conduct is derived from the Ruby code of conduct.
Any violations of the code of conduct may be reported
to Kirill Nesmeyanov ([nesk@xakep.ru](mailto:nesk@xakep.ru)).

- Participants will be tolerant of opposing views.

- Participants must ensure that their language and actions are free of
  personal attacks and disparaging personal remarks.

- When interpreting the words and actions of others, participants
  should always assume good intentions.

- Behavior which can be reasonably considered harassment will not be tolerated.

Unlike such PHP projects as Symfony, Composer, Laminas, JMS, etc., we **do not
violate** such rules and guarantee the absence of nationalistic and political
oppression.

**Open Source Software (OSS) is not a place for settling personal
relationships and propaganda.**

We pledge to act and interact in ways that contribute to an open, welcoming,
diverse, inclusive, and healthy community.

## Bugs

Whenever you find a bug in Boson Components, we kindly ask you to report it.
It helps us make a better code.

<warning>
If you think you've found a security issue, please use 
the <a href="contribution.md#security-issues">special procedure instead</a>.
</warning>

You may submit a bug report using GitHub Issues.
- [Documentation](https://github.com/boson-php/docs/issues)
- [Boson Kernel (monorepo)](https://github.com/boson-php/boson/issues)

Please follow some basic rules:

- Use the title field to clearly describe the issue;
- Describe the steps needed to reproduce the bug with short code examples;
- Give as much detail as possible about your environment (OS, PHP version, 
  component version, enabled extensions, ...);
- If there was an exception, and you would like to report it, it is valuable to 
  provide the stack trace for that exception.
  > Do not provide it as a screenshot, since search engines will not be able to 
  > index the text inside them. Same goes for errors encountered in a terminal, 
  > do not take a screenshot, but copy/paste the contents. 
  
  > Be wary that stack traces may contain **sensitive information**, and if 
  > it is the case, be sure to redact them prior to posting your stack trace.
  {style="warning"}


## Security Issues

If you think that you have found a security issue in Boson Component,
don't use the bug tracker and don't publish it publicly. Instead, all security
issues must be sent to [nesk@xakep.ru](mailto:nesk@xakep.ru).

### Bug Bounty

This is an Open-Source project where most of the work is done by volunteers.
We appreciate that developers are trying to find security issues in Boson
Components and report them responsibly, but we are currently unable to
pay bug bounties.

## Pull Request

Before you start, you should be aware that all the code you are going to
submit must be released under the [MIT license](https://opensource.org/license/mit).

A pull request (or "merge request"), "PR" for short, is the best way to
provide a bug fix or to propose enhancements to Boson Components.

<procedure title="#1: Setup your Environment">
  <step>Git;</step>
  <step>PHP 8.4 or above.</step>
</procedure>

<procedure title="#2: Get the Source Code">
  <step>
    Create a <a href="https://github.com">GitHub</a> account and sign in;
  </step>
  <step>
    Fork an expected repository (click on <shortcut>Fork</shortcut> button);
  </step>
  <step>
    Uncheck the "Copy the X.Y branch only";
  </step>
  <step>
    After the "forking action" has completed, clone your fork locally 
    (this will create a component directory):
    <code-block lang="Bash">
    git clone git@github.com:USERNAME/COMPONENT_NAME.git
    </code-block>
  </step>
  <step>
    Add the upstream repository as a remote:
    <code-block lang="Bash">
    cd COMPONENT_NAME
    git remote add upstream https://github.com/boson-php/COMPONENT_NAME.git
    </code-block>
  </step>
</procedure>

<procedure title="#3: Choose the right Branch">
  Since the project is quite simple, you can use the <code>master</code> branch for now.
</procedure>

<procedure title="#4: Work on your Pull Request">
  Work on the code as much as you want and commit as much as you want; 
  but keep in mind the following:
  <step>
    Add unit or functional tests to prove that the bug is fixed or 
    that the new feature actually works;
  </step>
  <step>
    Try hard to not break backward compatibility (if you must do so, try to 
    provide a compatibility layer to support the old way) &mdash; PRs that 
    break backward compatibility have less chance to be merged;
  </step>
  <step>
    Write good commit messages: Start by a short subject line (the first line), 
    followed by a blank line and a more detailed description.
  </step>
</procedure>

<procedure title="#5: Check that the current Tests Pass">
  Each Component contains a short Composer command that allows you to do this.
  <step>
    Checking the tests.
    <code-block lang="Bash">
    composer test
    </code-block>
  </step>
  <step>
    Checking and correcting coding style (we follow <a href="https://www.php-fig.org/per/coding-style/">PER Coding Style 2.0</a>).
    <code-block lang="Bash">
    composer phpcs:fix
    </code-block>
  </step>
  <step>
    Checking for other type errors in the code.
    <code-block lang="Bash">
    composer linter
    </code-block>
  </step>
</procedure>

<procedure title="#6: Submit your Pull Request">
  Whenever you feel that your PR is ready for submission, 
  follow the following steps.
  <step>
    Get all the latest changes to the branch
    <code-block lang="Bash">
    git fetch upstream
    git pull upstream master --ff
    </code-block>
  </step>
  <step>
    You can now make a pull request on GitHub repository.
  </step>
</procedure>
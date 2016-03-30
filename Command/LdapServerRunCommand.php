<?php

namespace CarnegieLearning\LdapOrmBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\ProcessBuilder;

class LdapServerRunCommand extends LdapServerCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', $this->address),
                new InputOption('port', 'p', InputOption::VALUE_REQUIRED, 'Address port numter', $this->port),
                new InputOption('base-dn', 'd', InputOption::VALUE_REQUIRED, 'BaseDN to set for the server', $this->baseDn),
                new InputOption('ldif', 'l', InputOption::VALUE_REQUIRED, 'LDIF to populate server with', $this->ldif),
            ])
            ->setName('ldap:server:run')
            ->setDescription('Runs UnboundID in-memory LDAP server')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> runs UnboundID in-memory LDAP server:

  <info>%command.full_name%</info>

To change default bind address and port use the <info>address</info> argument:

  <info>%command.full_name% 127.0.0.1:6389</info>

To change default LDIF file use the <info>--ldif</info> option:

  <info>%command.full_name% --ldif=Resources/ldif/sample.ldif</info>

To change the default BaseDN use the <info>--base-dn</info> option:

  <info>%command.full_name% --base-dn="dc=example,dc=com"</info>

See also: https://docs.ldap.com/ldap-sdk/docs/in-memory-directory-server.html

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $seed = $input->getOption('ldif');

        if (null === $seed) {
            $seed = '@CarnegieLearningLdapOrmBundle/Resources/ldap/sample.ldif';
        }

        if (0 === strpos($seed, '@', 0)) {
            $seed = $this
                  ->getContainer()
                  ->get('kernel')
                  ->locateResource($seed)
            ;
        }

        if (false === $path = realpath($seed)) {
            $io->error(sprintf('The LDIF seed file "%s" does not exist', $seed));

            return 1;
        }

        if (!is_readable($seed)) {
            $io->error(sprintf('The LDIF seed file "%s" is not readable', $seed));

            return 1;
        }

        $env = $this->getContainer()->getParameter('kernel.environment');
        $address = $input->getArgument('address');

        if (false === strpos($address, ':')) {
            $address = $address.':'.$input->getOption('port');
        }

        if ($this->isOtherServerProcessRunning($address)) {
            $io->error(sprintf('A process is already listening on ldap://%s', $address));

            return 1;
        }

        if ('prod' === $env) {
            $io->error('Running UnboundID in-memory LDAP server in production environment is NOT recommended!');
        }

        $io->success(sprintf('Server running on ldap://%s', $address));
        $io->comment('Quit the server with CONTROL-C.');

        $baseDn = $input->getOption('base-dn');

        if (null === $builder = $this->createLdapProcessBuilder($io, $address, $baseDn, $seed, $env)) {
            return 1;
        }

        $builder->setTimeout(null);
        $process = $builder->getProcess();

        if (OutputInterface::VERBOSITY_VERBOSE > $output->getVerbosity()) {
            $process->disableOutput();
        }

        $this
            ->getHelper('process')
            ->run($output, $process, null, null, OutputInterface::VERBOSITY_VERBOSE);

        if (!$process->isSuccessful()) {
            $errorMessages = ['UnboundID in-memory LDAP server terminated unexpectedly.'];

            if ($process->isOutputDisabled()) {
                $errorMessages[] = 'Run the command again with -v option for more details.';
            }

            $io->error($errorMessages);
        }

        return $process->getExitCode();
    }

    private function createLdapProcessBuilder(SymfonyStyle $io, $address, $baseDn, $seed, $env)
    {
        if (false === $script = realpath(__DIR__.'/../Resources/ldap/tools/in-memory-directory-server')) {
            $io->error('Unable to find UnboundID in-memory LDAP server script.');

            return;
        }

        list($host, $port) = explode(':', $address, 2);

        return new ProcessBuilder([$script, '--port', $port, '--baseDN', $baseDn, '--ldiffile', $seed]);
    }
}

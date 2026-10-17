<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\GroupEveryone\Command;

use OCA\GroupEveryone\AppInfo\Application;
use OCA\GroupEveryone\GroupBackend;
use OCP\IAppConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HideFromSharing extends Command {
	public function __construct(
		private IAppConfig $appConfig,
	) {
		parent::__construct();
	}

	protected function configure(): void {
		$this->setName('group_everyone:hide-from-sharing')
			->setDescription('Show or change whether the "everyone" group is hidden from sharing, including calendars and contacts')
			->addOption('on', null, InputOption::VALUE_NONE, 'hide the "everyone" group from sharing')
			->addOption('off', null, InputOption::VALUE_NONE, 'show the "everyone" group when sharing');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		$on = (bool)$input->getOption('on');
		$off = (bool)$input->getOption('off');
		if ($on && $off) {
			$output->writeln('<error>--on and --off cannot be used together</error>');
			return self::INVALID;
		}

		$hidden = $this->appConfig->getValueBool(Application::APP_ID, GroupBackend::CONFIG_HIDE_FROM_SHARING);
		if (($on || $off) && $on !== $hidden) {
			$hidden = $on;
			$this->appConfig->setValueBool(Application::APP_ID, GroupBackend::CONFIG_HIDE_FROM_SHARING, $hidden);
		}

		$output->writeln('The "everyone" group is ' . ($hidden ? 'hidden from' : 'shown when') . ' sharing');
		return self::SUCCESS;
	}
}

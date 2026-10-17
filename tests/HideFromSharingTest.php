<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\GroupEveryone\Tests;

use OCA\GroupEveryone\Command\HideFromSharing;
use OCP\IAppConfig;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Test\TestCase;

class HideFromSharingTest extends TestCase {
	public static function optionsProvider(): array {
		return [
			'show' => [false, [], Command::SUCCESS, false],
			'on' => [false, ['--on' => true], Command::SUCCESS, true],
			'off' => [true, ['--off' => true], Command::SUCCESS, false],
			'unchanged' => [true, ['--on' => true], Command::SUCCESS, true],
			'on and off' => [false, ['--on' => true, '--off' => true], Command::INVALID, false],
		];
	}

	#[DataProvider('optionsProvider')]
	public function testExecute(bool $hidden, array $options, int $exitCode, bool $expectedHidden): void {
		$appConfig = $this->createMock(IAppConfig::class);
		$appConfig->method('getValueBool')
			->with('group_everyone', 'hide_from_sharing')
			->willReturnCallback(function () use (&$hidden): bool {
				return $hidden;
			});
		$appConfig->method('setValueBool')
			->with('group_everyone', 'hide_from_sharing')
			->willReturnCallback(function (string $app, string $key, bool $value) use (&$hidden): bool {
				$hidden = $value;
				return true;
			});

		$tester = new CommandTester(new HideFromSharing($appConfig));
		$this->assertEquals($exitCode, $tester->execute($options));
		$this->assertEquals($expectedHidden, $hidden);
	}
}

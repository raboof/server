<?php

declare(strict_types=1);

/*
 * @copyright 2022 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @author 2022 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace lib\Security;

use OC\Http\Client\LocalAddressChecker;
use OC\Security\RemoteHostValidator;
use OCP\Http\Client\LocalServerException;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;

class RemoteHostValidatorTest extends TestCase {

	/** @var LocalAddressChecker|LocalAddressChecker&MockObject */
	private $addressChecker;
	private RemoteHostValidator $validator;

	protected function setUp(): void {
		parent::setUp();

		$this->addressChecker = $this->createMock(LocalAddressChecker::class);
		$this->validator = new RemoteHostValidator(
			$this->addressChecker,
		);
	}

	public function testValid(): void {
		$host = 'nextcloud.com';
		$this->addressChecker->expects(self::once())
			->method('throwIfLocalAddress')
			->with('nextcloud.com');

		$valid = $this->validator->isValid($host);

		self::assertTrue($valid);
	}

	public function testInvalid(): void {
		$host = 'localhost';
		$this->addressChecker->expects(self::once())
			->method('throwIfLocalAddress')
			->with('localhost')
			->willThrowException(new LocalServerException);

		$valid = $this->validator->isValid($host);

		self::assertFalse($valid);
	}
}

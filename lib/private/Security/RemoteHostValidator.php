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

namespace OC\Security;

use OC\Http\Client\LocalAddressChecker;
use OCP\Http\Client\LocalServerException;
use OCP\Security\IRemoteHostValidator;

/**
 * @internal
 */
final class RemoteHostValidator implements IRemoteHostValidator {
	private LocalAddressChecker $addressChecker;

	public function __construct(LocalAddressChecker $addressChecker) {
		$this->addressChecker = $addressChecker;
	}

	public function isValid(string $host): bool {
		try {
			$this->addressChecker->throwIfLocalAddress($host);
		} catch (LocalServerException $e) {
			return false;
		}
		return true;
	}
}

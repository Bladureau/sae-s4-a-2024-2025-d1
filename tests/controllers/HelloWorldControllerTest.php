<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace controllers;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class HelloWorldControllerTest extends TestCase
{

    private HelloWorldController $helloWorldController;

    public function setUp(): void
    {
        parent::setUp();
        // given a hello world  controller
        $this->helloWorldController = new HelloWorldController();
    }

    public function testIndex()
    {
        // when call to index
        $view = $this->helloWorldController->sayHello();
        // then the view point to the expected view file
        self::assertEquals("views/hello_you", $view->getRelativePath());
        // and the name is set
        self::assertEquals("no name", $view->getVar('name'));
    }
}

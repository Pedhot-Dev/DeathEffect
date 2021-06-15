<?php

/*
 *
 *  _____         _ _           _   _____
 * |  __ \       | | |         | | |  __ \
 * | |__) |__  __| | |__   ___ | |_| |  | | _____   __
 * |  ___/ _ \/ _` | '_ \ / _ \| __| |  | |/ _ \ \ / /
 * | |  |  __/ (_| | | | | (_) | |_| |__| |  __/\ V /
 * |_|   \___|\__,_|_| |_|\___/ \__|_____/ \___| \_/
 *
 *
 * Copyright 2021 Pedhot-Dev
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @author PedhotDev
 * @link https://github.com/Pedhot-Dev/DeathEffect
 *
 */

namespace pedhot\deatheffect\commands;

use pedhot\deatheffect\form\EffectForm;
use pedhot\deatheffect\User;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SelectEffectCommand extends Command
{

    public function __construct() {
        parent::__construct("selecteffect", "Select Death effect", null, ["sde", "se", "selectdeatheffect"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($this->testPermission($sender)) return;
        if (!$sender instanceof Player) return;

        (new EffectForm())->sendForm(new User($sender->getName()));
    }

}
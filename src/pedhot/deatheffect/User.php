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

namespace pedhot\deatheffect;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

use function is_null;
use function in_array;

class User
{

    /** @var string $name */
    private $name = "";

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSelectedEffect(): string {
        return isset(DeathEffect::getInstance()->data->getAll()[$this->getName()]["selected-effect"]) ? DeathEffect::getInstance()->data->getNested($this->getName().".selected-effect") : "default";
    }

    public function setSelectedEffect(string $name): void {
        DeathEffect::getInstance()->data->setNested($this->getName().".selected-effect", $name);
        DeathEffect::getInstance()->data->save();
    }

    public function getPlayer(): ?Player {
        return Server::getInstance()->getPlayerExact($this->getName());
    }

    public function sendMessage($message) {
        !is_null($this->getPlayer()) ? $this->getPlayer()->sendMessage($message) : MainLogger::getLogger()->alert($this->getName()." now offline!");
    }

    public function hasPermission(string $permission): bool {
        if (!in_array($permission, DeathEffect::getInstance()->data->getNested($this->getName().".effect-list"))) {
            return false;
        }
        return true;
    }

    public function hasData(): bool {
        return DeathEffect::getInstance()->data->exists($this->getName());
    }

    public function createData(): void {
        DeathEffect::getInstance()->data->setNested($this->getName().".selected-effect", ["selected-effect"=>"default", "effect-list"=>["deatheffect.default"]]);
    }

}
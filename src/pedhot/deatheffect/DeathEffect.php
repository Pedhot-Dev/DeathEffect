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

use pedhot\deatheffect\commands\SelectEffectCommand;
use pedhot\deatheffect\effects\DefaultEffect;
use pedhot\deatheffect\effects\Effects;
use pedhot\deatheffect\effects\LightningEffect;
use pedhot\deatheffect\utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class DeathEffect extends PluginBase
{

    /** @var self $instance */
    private static $instance;

    /** @var Config $data */
    public $data;

    /** @var Effects[] $effect */
    public $effect = [];

    public function getAllEffects(): array {
        return [
            "default" => 0,
            "lightning" => 100000
        ];
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function onLoad() {
        self::$instance = $this;
        Utils::checkConfig();
    }

    public function onEnable() {
        $this->data = new Config($this->getDataFolder()."data.yml", Config::YAML);
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);
        Server::getInstance()->getCommandMap()->register($this->getName(), new SelectEffectCommand());
    }

    public function getSelectedEffect(User $user) {
        switch ($user->getSelectedEffect()) {
            case "default":
                $this->effect[$user->getName()] = new DefaultEffect();
                break;
            case "lightning":
                $this->effect[$user->getName()] = new LightningEffect();
                break;
        }
    }

}
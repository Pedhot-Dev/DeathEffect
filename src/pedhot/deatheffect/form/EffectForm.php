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

namespace pedhot\deatheffect\form;

use jojoe77777\FormAPI\SimpleForm;
use onebone\economyapi\EconomyAPI;
use pedhot\deatheffect\DeathEffect;
use pedhot\deatheffect\User;
use pocketmine\Player;

use function ucfirst;

class EffectForm
{

    public function sendForm(User $user) {
        $form = new SimpleForm(function (Player $player, $data = null) use ($user): void {
            if ($data == null) return;

            if ($user->hasPermission("deatheffect.".$data)) {
                $user->setSelectedEffect($data);
                $user->sendMessage("§aSuccessfull select ".ucfirst($data)." effect");
            }else {
                if (EconomyAPI::getInstance()->myMoney($user->getPlayer()) < DeathEffect::getInstance()->getAllEffects()[$data]) {
                    $user->getPlayer()->sendTip("§cNot enough money!");
                }else {
                    $array = DeathEffect::getInstance()->data->getNested($user->getName().".effect-list", []);
                    $array[] = "deatheffect.".$data;
                    DeathEffect::getInstance()->data->setNested($user->getName().".effect-list", $array);
                    DeathEffect::getInstance()->data->save();
                    EconomyAPI::getInstance()->setMoney($user->getPlayer(), EconomyAPI::getInstance()->myMoney($user->getPlayer()) - DeathEffect::getInstance()->getAllEffects()[$data]);
                    $user->sendMessage("§aSuccessfull bought ".$data." effect!");
                }
            }
            if ($user->getSelectedEffect() == $data) {
                $user->sendMessage("§c".$data." effect has selected!");
            }
        });
        $form->setTitle("Select effect");
        $check = function (User $user, $name, $cost): string {
            $text = "";
            if ($user->hasPermission("deatheffect.".$name)) {
                $text = "§l§1".ucfirst($name)."\n§r§7Select";
            }else {
                $text = "§l§8".ucfirst($name)." §r§7- ".$this->thousandsCurrencyFormat($cost)." Coins\n§7".ucfirst($name)." effect";
            }
            if ($user->getSelectedEffect() == $name) {
                $text = "§l§d".ucfirst($name)."\n§2»§r§2 Selected §r§l§2«";
            }
            return $text;
        };
        foreach (DeathEffect::getInstance()->getAllEffects() as $name => $cost) {
            $form->addButton($check($user, $name, $cost), -1, "", $name);
        }
        $form->sendToPlayer($user->getPlayer());
    }

    private function thousandsCurrencyFormat($num) {
        if($num >= 1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            return $x_display;
        }
        return $num;
    }

}

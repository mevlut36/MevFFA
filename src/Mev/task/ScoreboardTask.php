<?php

namespace Mev\task;

use Mev\Main;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\scheduler\Task;

class ScoreboardTask extends Task {

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function OnRun(int $tick){
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
            $this->plugin->Scoreboard($player);
            $pk = new RemoveObjectivePacket();
            $pk->objectiveName = "test";
            $player->sendDataPacket($pk);
            $this->plugin->Scoreboard($player);
        }
     //   $this->plugin->getLogger()->info("ScoreBoard update !");
    }
}
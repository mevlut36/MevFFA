<?php

namespace Mev\task;

use Mev\Main;
use Mev\task\ScoreboardTask;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Armor;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ArmorInventory;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat as TF;
use pocketmine\math\Vector3;

class DeathTask extends Task {

	public $back = 5;
	public $player;
	public $plugin;
	
    public function __construct(Main $plugin, Player $player){
		$this->plugin = $plugin;
		$this->player = $player;
	}

    public function onRun($tick) {
        if($this->back != 0) {
        	$this->back--;
  			$this->player->sendMessage(TF::YELLOW . "[FFA] You will be revived in " . $this->start . " seconds");
		} 
        if($this->back == 1) {
          	  $arenalevel = $this->plugin->getServer()->getLevelByName("world");
       	 	$arenaspawn = $arenalevel->getSafeSpawn();
       	 	$this->player->teleport($arenaspawn);
            	$this->player->sendMessage(TF::DARK_PURPLE . "You have been revived.");
                $this->player->setGamemode(2);
                $this->player->getInventory()->clearAll();
        }
    }
}

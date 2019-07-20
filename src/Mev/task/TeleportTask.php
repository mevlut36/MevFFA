<?php

namespace Mev\task;

use Mev\Main;
use Mev\task\ScoreboardTask;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Armor;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

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

class TeleportTask extends Task {

	public $start = 5;
	public $player;
	public $plugin;
	
    public function __construct(Main $plugin, Player $player){
		$this->plugin = $plugin;
		$this->player = $player;
	}

    public function onRun($tick) {
        if($this->start != 0) {
        	$this->start--;
  			$this->player->sendMessage(TF::YELLOW . "[FFA] You will teleported in " . $this->start . " seconds");
		} 
        if($this->start == 1) {
        	    $x = mt_rand(-12, 60);
           	 	$y = mt_rand(40, 41);
           	 	$z = mt_rand(-14, 50);
           
          	    $arenalevel = $this->plugin->getServer()->getLevelByName("ffa");
       	 		$arenaspawn = $arenalevel->getSafeSpawn();
       	 		$this->player->teleport(new Position($x, $y, $z, $arenalevel));
            	$this->player->sendMessage(TF::DARK_PURPLE . "You have joined FFA-1.");
                $this->player->setGamemode(0);
                $this->player->setHealth(20);
                $this->player->setFood(20);
                $this->player->getInventory()->clearAll();
      		    $helmet = Item::get(310);
				$chestplate = Item::get(311);
				$leggings = Item::get(312); 
				$boots = Item::get(313); 
				$sword = ItemFactory::get(Item::DIAMOND_SWORD); 
				$apple = ItemFactory::get(Item::GOLDEN_APPLE);
				$sword->clearCustomName(); 
				$sword->setCustomName("§bSword"); 
				$this->player->getArmorInventory()->setHelmet($helmet); 
				$this->player->getArmorInventory()->setChestplate($chestplate); 
				$this->player->getArmorInventory()->setLeggings($leggings); 
				$this->player->getArmorInventory()->setBoots($boots); 
				$this->player->getInventory()->addItem($sword);
				$this->player->getInventory()->addItem(Item::get(ITEM::GOLDEN_APPLE, 0, 12));
				$this->player->getInventory()->setItem(2, Item::get(Item::DIAMOND_PICKAXE, 0, 1));
				$this->player->getInventory()->setItem(3, Item::get(Item::SANDSTONE, 0, 64));
				$this->player->getInventory()->setItem(4, Item::get(Item::SANDSTONE, 0, 64));
				$this->player->getInventory()->setItem(5, Item::get(Item::SANDSTONE, 0, 64));
				$this->player->getInventory()->setItem(6, Item::get(Item::SANDSTONE, 0, 64));
				$this->player->getInventory()->setItem(7, Item::get(Item::SANDSTONE, 0, 64));
				$this->player->getInventory()->setItem(8, Item::get(Item::SANDSTONE, 0, 64));
        }
    }
}

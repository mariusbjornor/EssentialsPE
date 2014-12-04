<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Jump extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "jump", "Teleport you to the block you're looking at", "/jump", ["j", "jumpto"]);
        $this->setPermission("essentials.jump");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 0){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
        }
        $transparent = [];
        $block = $sender->getTargetBlock(100, $transparent);
        while(!$block->isSolid){
            if($block === null){
                break;
            }
            $transparent[] = $block->getID();
            $block = $sender->getTargetBlock(100, $transparent);
        }
        if($block === null){
            $sender->sendMessage(TextFormat::RED . "There isn't a reachable block");
            return false;
        }
        //TODO Check for secure teleport
        $sender->teleport($block->add(0, 1));
        return true;
    }
}

<?php

namespace VirVolta\ItemEffect;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\sound\PopSound;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class ItemEffect extends PluginBase implements Listener
{
    private $config;
    private $interact = [];

    /**
     * @param mixed $config
     */
    public function setData(Config $config): void
    {
        $this->config = $config;
    }

    public function getData(): Config
    {
        return $this->config;
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());

        if(!file_exists($this->getDataFolder()."config.yml")){

            $this->saveResource('config.yml');

        }

        $this->setData(new Config($this->getDataFolder().'config.yml', Config::YAML));
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $configs = $this->config->getAll();
        $data = $this->getInArray($item->getId(),$item->getDamage());

        if (!is_null($this->getinArray($item->getId(),$item->getDamage()))) {

            $id = Item::fromString($data);

            if ($item->getId() === $id->getId() and ($id->getDamage() == 0 or $item->getDamage() == $id->getDamage())) {


                $array = $configs[$data];

                if ($this->onCountDown($player,$array["countdown"])) {

                    if ($array["consume"]) {

                        $player->getInventory()->removeItem(Item::get($id->getId(),$id->getDamage(),1));

                    }

                    if ($array["message"] != null) {

                        $player->sendMessage($array["message"]);

                    }

                    $effects = $array["effect"];

                    foreach ($effects as $effectid => $arrayeffect) {

                        $eff = new EffectInstance(

                            Effect::getEffect($effectid) ,
                            (int)$arrayeffect["durability"] * 20 ,
                            (int)$arrayeffect["amplifier"],
                            (bool)$arrayeffect["visible"]
                        );
                        $player->addEffect($eff);

                    }

                    $event->setCancelled();

                }

            }

        }

    }

    public function onCountDown(Player $player , int $countdown) : bool
    {
        if ($countdown <= 0.5) {

            $countdown = 0.5;

        }

        if(isset($this->interact[strtolower($player->getName())]) &&

            time() - $this->interact[strtolower($player->getName())]  < $countdown) {
            return false;

        } else {

            $this->interact[strtolower($player->getName())] = time();

        }

        return true;
    }

    public function getInArray(int $id, int $damage)
    {
        $configs = $this->getData()->getAll();
        $ids = array_keys($configs);

        if (in_array("$id",$ids)) {

            return "$id";

        } else if (in_array("$id:$damage",$ids)) {

            return "$id:$damage";

        }

        return null;
    }

}

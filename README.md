# ItemEffect

Item Effect plugin for PocketMine-MP

## Using Plugin

| Property | Description | Default |
|---|---|---|
| id | The id and meta of the item | 351:1 | 
| countdown | The waiting period of the next use of the item in sec | 5 |
| consume | Allows to have only one use per item | false |
| message | Adds a message each time the item is used | null |
| effect | Adds a effect |  |

### Getting the config.yml of the plugin

```YAML
#The id of the item that will add the effects
"id:metadata":
  #The waiting period of the next use of the item in sec
  countdown: number 
  #Allows to have only one use per item
  consume: true or false
  #If you don't want a message put null
  message: null or "message"

  effect:

    #The id of the effects
    6:
      #Allows you to add the time in sec to the effect
      durability: number
      #Allows to add a specific force to the effect
      amplifier: number
      #Allows you to set yes or no particles to the effect
      visible: true or false
```

import React, { Component } from 'react';
import Sheep from './Sheep';
import Hud from './Hud';
import $ from 'jquery';

export default class Game extends Component {
  state = {
    animation: null,
    health: null,
    hunger: null,
    playful: null,
    sleepiness: null,
    weight: null,
    wealth: null,
  };

  componentDidMount() {
      const that = this;
      const websocket = WS.connect('ws://127.0.0.1:1337/');

      websocket.on("socket/connect", function(session){
          console.log("connection OK");

          session.subscribe("output/application", function(uri, payload){
              console.log("Received message", payload);
              console.log(payload);

              that.setState(() => ({
                  animation: payload.slice(1,-1)
              }));

              $.get("http://127.0.0.1:8000/tamagotchi", function( tamagotchi ) {
                  console.table(tamagotchi);

                  this.setState(() => ({
                      health: tamagotchi.health,
                      hunger: tamagotchi.hunger,
                      playful: tamagotchi.playful,
                      sleepiness: tamagotchi.sleepiness,
                      weight: tamagotchi.weight,
                      wealth: tamagotchi.wealth,
                      animation: payload
                  }));
              });
          });
      });

      websocket.on("socket/disconnect", function(error){
          //error provides us with some insight into the disconnection: error.reason and error.code
          console.log("Disconnected for " + error.reason + " with code " + error.code);
      });
  }

  render () {
    return (
      <div id="world">
        <div id="sky">&nbsp;</div>
        <div id="grass_front">&nbsp;</div>
        <Hud
            health={this.state.health}
            hunger={this.state.hunger}
            playful={this.state.playful}
            sleepiness={this.state.sleepiness}
            weight={this.state.weight}
            wealth={this.state.wealth}
        />
        <Sheep animation={this.state.animation} />

        <div id="grass_back">&nbsp;</div>
        <div id="treeone">&nbsp;</div>
        <div id="treetwo">&nbsp;</div>
        <div id="rockone">&nbsp;</div>
        <div id="rocktwo">&nbsp;</div>
      </div>
    );
  }
}

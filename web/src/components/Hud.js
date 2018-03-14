import React, { Component } from 'react';

export default class Hud extends Component {
    state = {
        health: null,
        hunger: null,
        playful: null,
        sleepiness: null,
        weight: null,
        wealth: null,
    };

    updateHud = (props) => {
        this.setState(() => ({
            health: props.health,
            hunger: props.hunger,
            playful: props.playful,
            sleepiness: props.sleepiness,
            weight: props.weight,
            wealth: props.wealth
        }));
    };

    componentDidMount() {
        this.updateHud(this.props);
    }

    componentWillReceiveProps(nextProps)  {
        this.updateHud(nextProps);
    };

  render () {
    return (
      <div id="hud">
        <div className={`heart ${this.state.health <= 0 && 'inactive'}`}>&nbsp;</div>
        <div className={`heart ${this.state.health <= 2 && 'inactive'}`}>&nbsp;</div>
        <div className={`heart ${this.state.health <= 4 && 'inactive'}`}>&nbsp;</div>
        <div className={`heart ${this.state.health <= 6 && 'inactive'}`}>&nbsp;</div>
        <div className={`heart ${this.state.health <= 8 && 'inactive'}`}>&nbsp;</div>

        <div className="spacing">&nbsp;</div>

        <div className={`hungry ${this.state.hunger <= 0 && 'inactive'}`}>&nbsp;</div>
        <div className={`hungry ${this.state.hunger <= 2 && 'inactive'}`}>&nbsp;</div>
        <div className={`hungry ${this.state.hunger <= 4 && 'inactive'}`}>&nbsp;</div>
        <div className={`hungry ${this.state.hunger <= 6 && 'inactive'}`}>&nbsp;</div>
        <div className={`hungry ${this.state.hunger <= 8 && 'inactive'}`}>&nbsp;</div>

        <div className="spacing">&nbsp;</div>

        <div className={`playful ${this.state.playful <= 0 && 'inactive'}`}>&nbsp;</div>
        <div className={`playful ${this.state.playful <= 2 && 'inactive'}`}>&nbsp;</div>
        <div className={`playful ${this.state.playful <= 4 && 'inactive'}`}>&nbsp;</div>
        <div className={`playful ${this.state.playful <= 6 && 'inactive'}`}>&nbsp;</div>
        <div className={`playful ${this.state.playful <= 8 && 'inactive'}`}>&nbsp;</div>

        <div className="spacing">&nbsp;</div>

        <div className={`sleep ${this.state.sleepiness <= 0 && 'inactive'}`}>&nbsp;</div>
        <div className={`sleep ${this.state.sleepiness <= 2 && 'inactive'}`}>&nbsp;</div>
        <div className={`sleep ${this.state.sleepiness <= 4 && 'inactive'}`}>&nbsp;</div>
        <div className={`sleep ${this.state.sleepiness <= 6 && 'inactive'}`}>&nbsp;</div>
        <div className={`sleep ${this.state.sleepiness <= 8 && 'inactive'}`}>&nbsp;</div>
      </div>
    );
  }
}

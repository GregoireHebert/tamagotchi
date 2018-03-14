import React, { Component } from 'react';

export default class Sheep extends Component {
  state = {
    direction: Math.random() < 0.5 ? 'left' : 'right',
    moving: false,
    style: {
      left: '35%'
    }
  };

  /**
   * - Get `left` property from stylesheets, and update state
   * - Reset classes
   */
  updatePositionAndReset = () => {
    const left = window.getComputedStyle(this.sheep).getPropertyValue('left');

    this.setState(() => ({
      style: { left }
    }));

    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`idle_${this.state.direction}`);
  };

  run = () => {
    if (this.state.moving) return;
    this.setState(() => ({ moving: true }));

    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`run_${this.state.direction}`);

    setTimeout(() => {
      this.updatePositionAndReset();

      setTimeout(() => {
        this.setState((prevState) => ({
          direction: prevState.direction === 'right' ? 'left' : 'right'
        }), () => {
          this.sheep.classList.add(`run_${this.state.direction}`);
        });

        setTimeout(() => {
          this.updatePositionAndReset();
          this.setState(() => ({ moving: false }));
        }, Math.random() * 3000 + 1000);
      }, 1000);
    }, 4900);
  };

  die = () => {
    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`die_${this.state.direction}`);
    this.setState(() => ({ moving: false }));
  };

  idle = () => {
    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`idle_${this.state.direction}`);
    this.setState(() => ({ moving: false }));
  };

  eat = () => {
    if (this.state.moving) return;
    this.setState(() => ({ moving: true }));

    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`grab_${this.state.direction}`);

    setTimeout(() => {
      this.sheep.removeAttribute('class');
      this.sheep.classList.add(`chew_${this.state.direction}`);

        setTimeout(() => {
            this.sheep.removeAttribute('class');
            this.sheep.classList.add(`swallow_${this.state.direction}`);

            setTimeout(() => {
                this.updatePositionAndReset();
                this.setState(() => ({ moving: false }));
            }, 700);

        }, 4500);

    }, 700);
  };

  walk = () => {
    if (this.state.moving) return;
    this.setState(() => ({ moving: true }));

    this.sheep.removeAttribute('class');
    this.sheep.classList.add(`walk_${this.state.direction}`);

    setTimeout(() => {
      this.updatePositionAndReset();

      setTimeout(() => {
        this.setState((prevState) => ({
          direction: prevState.direction === 'right' ? 'left' : 'right'
        }), () => {
          this.sheep.classList.add(`walk_${this.state.direction}`);
        });

        setTimeout(() => {
          this.updatePositionAndReset();
          this.setState(() => ({ moving: false }));
        }, Math.random() * 5000 + 2000);
      }, 1000);
    }, 9000);
  };

  animate = (animation) => {
    switch (animation) {
      case 'run':
        return this.run();
      case 'walk':
        return this.walk();
      case 'eat':
        return this.eat();
      case 'die':
        return this.die();
      case 'idle':
        return this.idle();
      default:
        return this.idle();
    }
  };

  componentDidMount() {
    this.animate(this.props.animation);
  }

  componentWillReceiveProps(nextProps)  {
    this.animate(nextProps.animation);
  };

  render() {
    // Fake style for dev
    const nav = {
      position: 'fixed',
      zIndex: 10,
      top: '20px',
      left: '20px'
    };

    return (
      <div>
        {/* for dev only */}
        {/*<div className="nav" style={nav}>*/}
          {/*<button onClick={this.run}>Run</button>*/}
          {/*<button onClick={this.die}>Die</button>*/}
          {/*<button onClick={this.idle}>Idle</button>*/}
          {/*<button onClick={this.walk}>Walk</button>*/}
          {/*<button onClick={this.eat}>Eat</button>*/}
        {/*</div>*/}

        <div
          id="sheep"
          className="idle_left"
          ref={(sheep) => {
            this.sheep = sheep;
          }}
          style={this.state.style}
        >
          &nbsp;
        </div>
      </div>
    )
  }
}

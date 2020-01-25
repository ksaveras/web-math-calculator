import React from "react";
import Input from "./Input";

export default class App extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      history: [],
      expression: '',
    };

    this.onChange = this.onChange.bind(this);
    this.calculate = this.calculate.bind(this);
  }

  onChange(event) {
    this.setState({expression: event.target.value});
  }

  calculate() {
    // if (this.state.expression.trim().length === 0) {
    //   return;
    // }

    fetch('/calculate', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        expression: this.state.expression
      })
    })
      .then(response => Promise.all([response.ok, response.json()]))
      .then(([responseOk, body]) => {
        if (responseOk) {
          const mathResult = body.expression + ' = ' + body.result;
          this.appendResult(mathResult);
        } else {
          const {message = 'Unknown error occurred'} = body;
          throw new Error(message);
        }
      })
      .catch(e => {
        const mathResult = this.state.expression + ' = ERROR ' + e.message;
        this.appendResult(mathResult);
      });
  }

  appendResult(mathResult) {
    const history = [...this.state.history, mathResult];
    this.setState({history, expression: ''});
  }

  render() {
    const answers = this.state.history.join("\n");

    return (
      <div>
        <div>
          <h2>Simple Math Calculator</h2>
          <div>
            <div className="pb-2">
              <textarea className="form-control no-resize" readOnly={true} rows="10" value={answers}/>
            </div>
          </div>
        </div>
        <Input value={this.state.expression} changeHandler={this.onChange} clickHandler={this.calculate}/>
      </div>
    )
  }
}

import React, {Component} from 'react';
import {
    Label, Input, HelpBlock, Button, Panel, PanelHeading, PanelBody
} from '../../components';

const validUrl = require('valid-url');

class Home extends Component {

    constructor(props) {
        super(props);

        this.state = {
            form: {
                url: 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2',
            },
            paginator: {
                hasMore: true,
                data: [],
            },
            meta: {
                loading: false
            },
            validationErrors: [],
        };

        this.onChange = this.onChange.bind(this);
        this.validateAndSubmit = this.validateAndSubmit.bind(this);
    }

    onChange(e) {
        const {form} = this.state;
        form[e.target.name] = e.target.value;
        this.setState({form});
    }

    validateAndSubmit(e) {
        e.preventDefault();

        if (!this.validate()) {
            this.addValidationError('This value is not a valid URL.');
            return;
        }
        this.removeValidationError();
        this.submit();
    }

    validate() {
        const {form} = this.state;

        if (!!form.url) {
            return this.validateUrl(form.url);
        }

        return false;
    }

    validateUrl(url) {
        return validUrl.isUri(url);
    }

    addValidationError(error) {
        const {validationErrors} = this.state;
        validationErrors.push(error);
        this.setState({validationErrors});
    }

    removeValidationError() {
        const validationErrors = [];
        this.setState({validationErrors});
    }

    submit() {
        this.startRequest();

        const {form} = this.state;

        axios.post('/feed', form)
            .then(response => {
                console.log('response:', response.data);

                this.requestComplete();
            })
            .catch(error => {
                error.response.data.data.forEach((error) => {
                    this.addValidationError(error);
                });

                this.requestComplete();
            });
    }

    startRequest() {
        const {meta} = this.state;
        meta.loading = true;
        this.setState({meta});
    }

    requestComplete() {
        const {meta} = this.state;
        meta.loading = false;
        this.setState({meta});
    }

    renderInputBox() {
        const {form} = this.state;
        return (
            <div className={'form-group ' + (this.state.validationErrors.length > 0 ? 'has-error' : '')}>
                <Label htmlFor="url" label="Enter Feed URL"/>
                <Input type="text" className="form-control" name="url" value={form.url} onChange={this.onChange}/>
                <HelpBlock
                    isHidden={this.state.validationErrors.length === 0}>{this.state.validationErrors.length > 0 ? this.state.validationErrors[0] : ''}</HelpBlock>
            </div>
        )
    }

    renderInputButton() {
        const {meta, form} = this.state;

        const disabled = !form.url || meta.loading;

        return (
            <div className="form-group">
                <Button className="btn btn-primary" disabled={disabled}
                        onClick={this.validateAndSubmit}>Submit</Button>
            </div>
        )
    }

    renderResultBox() {

    }

    render() {
        return (
            <div className="row">
                <div className="col-md-12">

                    <Panel panel="default">

                        <PanelHeading>
                            Feed Display
                        </PanelHeading>

                        <PanelBody>
                            <div className="row">
                                <div className="col-md-8 col-md-offset-2">
                                    {this.renderInputBox()}
                                    {this.renderInputButton()}
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-md-12">
                                    {this.renderResultBox()}
                                </div>
                            </div>
                        </PanelBody>

                    </Panel>

                </div>
            </div>
        )
    }
}

export default Home;
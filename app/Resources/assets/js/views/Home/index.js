import React, {Component} from 'react';
import {
    Label, Input, HelpBlock, Button, Panel, PanelHeading, PanelBody
} from '../../components';
import ProductRow from './ProductRow';
import Product from '../../models/Product';
import ProductModal from './ProductModal';

const validUrl = require('valid-url');

class Home extends Component {

    constructor(props) {
        super(props);

        this.state = {
            form: {
                url: 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2',
                limit: 10,
                forceRefresh: false
            },
            data: [],
            meta: {
                loading: false
            },
            validationErrors: [],
            selectedProduct: new Product()
        };

        this.onChange = this.onChange.bind(this);
        this.validateAndSubmit = this.validateAndSubmit.bind(this);
        this.showProductDetail = this.showProductDetail.bind(this);
    }

    onChange(e) {
        const {form} = this.state;
        if (e.target.name === 'limit') {
            form[e.target.name] = parseInt(e.target.value);
        } else if (e.target.name === 'forceRefresh') {
            form[e.target.name] = e.target.checked;
        } else {
            form[e.target.name] = e.target.value;
        }
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

    submit(page = 1) {
        this.startRequest();

        const form = JSON.parse(JSON.stringify(this.state.form));
        form.page = page;

        axios.post('/feed', form)
            .then(response => {

                this.updateResponse(response.data.data);

                this.requestComplete();
            })
            .catch(error => {
                const {response} = error;
                if (!!response && !!response.data) {
                    response.data.data.forEach((error) => {
                        this.addValidationError(error);
                    });
                }

                this.requestComplete();
            });
    }

    updateResponse(data) {
        this.setState({data});
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

    renderURLInputBox() {
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

    renderLimitInputBox() {
        const {form} = this.state;
        return (
            <div className="form-group">
                <Label htmlFor="limit" label="Limit Feed by"/>
                <select name="limit" className="form-control" value={form.limit} onChange={this.onChange}>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        )
    }

    renderForceRefreshBox() {
        const {form} = this.state;
        return (
            <div className="form-group">
                <Label htmlFor="limit" label="Don't use Cache"/> <br/>
                <Input type="checkbox" name="forceRefresh" value={form.forceRefresh} onChange={this.onChange}/>
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

    renderTableHead() {
        return (
            <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            </thead>
        )
    }

    showProductDetail(e, selectedProduct) {
        e.preventDefault();
        this.setState({selectedProduct});

        $('#product-modal').modal()
    }

    renderTableBody() {

        const {data} = this.state;

        if (data.length === 0) {
            return (
                <tbody>
                <tr>
                    <td colSpan={4}>Click Submit to Load Data!</td>
                </tr>
                </tbody>
            )
        }

        return (
            <tbody>
            {data.map((product, index) => {
                return <ProductRow
                    key={product.productID}
                    product={new Product(product)}
                    showProductDetail={this.showProductDetail}/>
            })}
            </tbody>
        )
    }

    renderResultBox() {

        return (
            <table className="table table-striped table-responsive">
                {this.renderTableHead()}
                {this.renderTableBody()}
            </table>
        )
    }

    renderProductModal() {
        return (
            <ProductModal product={this.state.selectedProduct}/>
        )
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

                            {this.renderProductModal()}

                            {this.renderURLInputBox()}
                            {this.renderLimitInputBox()}
                            {this.renderForceRefreshBox()}
                            {this.renderInputButton()}

                            {this.renderResultBox()}

                        </PanelBody>
                    </Panel>

                </div>
            </div>
        )
    }
}

export default Home;
import React, {Component} from 'react';


class ProductRow extends Component {
    constructor(props) {
        super(props);
    }

    renderActions(product) {
        return (
            <td>
                <button className="btn btn-default btn-sm" onClick={e => {
                    this.props.showProductDetail(e, product)
                }}>Detail
                </button>
            </td>
        )
    }

    render() {
        const {product, index} = this.props;

        return (
            <tr>
                <td>{product.getId()}</td>
                <td>{product.getName(25)}</td>
                <td>{product.getDescription(20)}</td>
                <td>{product.getPrice() + ' ' + product.getCurrency()}</td>
                {this.renderActions(product)}
            </tr>
        )
    }
}

export default ProductRow;
import React, {Component} from 'react';

class ProductModal extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const {product} = this.props;
        return (
            <div className="modal fade" id="product-modal" role="dialog" aria-labelledby="myModalLabel">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                            <strong className="modal-title" id="myModalLabel">
                                {product.getName()}
                            </strong>
                        </div>
                        <div className="modal-body">
                            <div className="row">
                                <div className="col-md-4">
                                    <img src={product.getImageUrl()} className="thumbnail img-responsive" alt=""/>
                                </div>

                                <div className="col-md-8">
                                    <table className="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td>ID</td>
                                            <td>{product.getId()}</td>
                                        </tr>
                                        <tr>
                                            <td>Name</td>
                                            <td>{product.getName()}</td>
                                        </tr>
                                        <tr>
                                            <td>Price</td>
                                            <td>{product.getPrice() + ' ' + product.getCurrency()}</td>
                                        </tr>
                                        <tr>
                                            <td>Categories</td>
                                            <td>{product.getCategory()}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-md-12">
                                    <strong>Description:</strong>
                                    {product.getDescription()}
                                </div>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <a href={product.getProductURL()} target="_blank" className="btn btn-primary btn-sm">View Product Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default ProductModal;
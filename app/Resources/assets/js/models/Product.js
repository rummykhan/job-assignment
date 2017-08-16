import _ from 'lodash';

class Product {

    constructor(data) {
        this.init(data);
    }

    init(data) {
        _.assign(this, data);
    }

    getId() {
        return this.productID;
    }

    getName(limit) {
        if (!!limit) {
            return this.name.substr(0, limit) + '...';
        }

        return this.name;
    }

    getDescription(limit) {

        if (!!limit) {
            return this.description.substr(0, limit) + '...';
        }

        return this.description;
    }

    getPrice() {
        return this.price;
    }

    getCurrency() {
        if (!!this['attributes']) {
            return this['attributes']['price.0']['currency'];
        }

        return 0;
    }

    getImageUrl() {
        return this.imageURL;
    }

    getProductURL() {
        return this.productURL;
    }

    getCategory() {
        return this.category;
    }
}

export default Product;
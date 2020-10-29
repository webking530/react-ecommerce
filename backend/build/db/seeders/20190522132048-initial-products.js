'use strict';

var config = require('../../config');

module.exports = {
  up: function up(queryInterface, Sequelize) {
    return queryInterface.bulkInsert('Products', [{
      img: config.remote + '/assets/products/img1.jpg',
      title: 'trainers',
      subtitle: 'Trainers In White',
      price: 76,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img2.jpg',
      title: 'boots',
      subtitle: 'Trainers In Blue',
      price: 45,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainer",
      technology: "Ollie patch",
      discount: 20,
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img3.jpg',
      title: 'flat sandals',
      subtitle: 'Trainers In White',
      price: 55,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img4.jpg',
      title: 'trainers',
      subtitle: 'Trainers In White',
      price: 76,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img5.jpeg',
      title: 'boots',
      subtitle: 'Trainers In Blue',
      price: 45,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      discount: 20,
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img6.jpg',
      title: 'flat sandals',
      subtitle: 'Trainers In White',
      price: 55,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img1.jpg',
      title: 'trainers',
      subtitle: 'Trainers In White',
      price: 76,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img2.jpg',
      title: 'boots',
      subtitle: 'Trainers In Blue',
      price: 45,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      discount: 20,
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img3.jpg',
      title: 'flat sandals',
      subtitle: 'Trainers In White',
      price: 55,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img4.jpg',
      title: 'trainers',
      subtitle: 'Trainers In White',
      price: 76,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img5.jpeg',
      title: 'boots',
      subtitle: 'Trainers In Blue',
      price: 45,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      discount: 20,
      createdAt: new Date(),
      updatedAt: new Date()
    }, {
      img: config.remote + '/assets/products/img6.jpg',
      title: 'flat sandals',
      subtitle: 'Trainers In White',
      price: 55,
      rating: 4.6,
      description_1: "Sneakers (also known as athletic shoes, tennis shoes,gym shoes, runners, takkies, or trainers) are shoes primarily designed for sports or other forms of physical exercise, but which are now also often used for everyday wear.",
      description_2: "The term generally describes a type of footwear with a flexible sole made of rubber or synthetic material and an upper part made of leather or synthetic materials.",
      code: 135234,
      hashtag: "whitetrainers",
      technology: "Ollie patch",
      createdAt: new Date(),
      updatedAt: new Date()
    }], {});
  },
  down: function down(queryInterface, Sequelize) {
    return queryInterface.bulkDelete('Products', null, {});
  }
};
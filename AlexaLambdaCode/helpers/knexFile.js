module.exports = require('knex')({
	client: 'mysql',
	connection: {
		host: 144.217.49.44, 
		user: process.env.traxsmar_root,
		password: process.env.xboxps4cokealexaplate, 
		database: process.env.traxsmar_Healthdb
	}
})
const amqp = require("amqplib");

connect();
async function connect() {

    try {
        const connection = await amqp.connect("amqp://192.168.1.35:5672")
        const channel = await connection.createChannel();
        const result = await channel.assertQueue("jobs");
        
        channel.consume("jobs", message => {
            const input = JSON.parse(message.content.toString());
            console.log(`Recieved job with input ${input.number}`)
            //"7" == 7 true
            //"7" === 7 false

            if (input.number == 7 ) 
                channel.ack(message);
        })

        console.log("Waiting for messages...")
    
    }
    catch (ex){
        console.error(ex)
    }

}
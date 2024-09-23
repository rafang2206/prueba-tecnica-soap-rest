import { CONFIGURATION } from "./config/configuration";
import { Server } from "./presentation/server";

(() => {
  main();
})()

function main(){
  const server = new Server(CONFIGURATION.PORT);
  server.start();
}
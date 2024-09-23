import { Server } from "./presentation/server";

(() => {
  main();
})()

function main(){
  const server = new Server(3000);
  server.start();
}
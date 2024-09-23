import { CONFIGURATION } from "../src/config/configuration";
import { Server } from "../src/presentation/server";

const testServer = new Server(CONFIGURATION.PORT);

export {
  testServer
};
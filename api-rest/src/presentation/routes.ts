import { Router } from "express";
import { BuyRoutes } from "./buys/buys.routes";
import { UserRoutes } from "./users/users.routes";
import { WalletRoutes } from "./wallets/wallets.routes";

export class AppRoutes {

  static get routes() {

    const routes = Router();

    routes.use('/user', UserRoutes.routes);

    routes.use('/buy', BuyRoutes.routes)

    routes.use('/wallet', WalletRoutes.routes)


    return routes;
  }
}
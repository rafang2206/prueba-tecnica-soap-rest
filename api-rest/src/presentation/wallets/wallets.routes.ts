import { Router } from "express";
import { WalletRespositoryImpl } from "../../infrastructure/repository/wallet.repositoryimpl";
import { WalletHandlerDatasource } from "../../infrastructure/datasource/wallet-handler.datasource";
import { WalletController } from "./wallets.controller";
import validatorHandler from "../middlewares/validator.handler";
import { walletBalanceSchema, walletRechargeSchema } from "../../domain/validation/wallet.validation";

export class WalletRoutes {
  static get routes() {
    const routes = Router();

    const walletRepository = new WalletRespositoryImpl(
      new WalletHandlerDatasource
    )

    const userController = new WalletController(walletRepository);

    routes.get(
      '/balance',
      validatorHandler(walletBalanceSchema, 'query'),
      userController.getBalance
    );

    routes.post(
      '/recharge-wallet',
      validatorHandler(walletRechargeSchema, 'body'),
      userController.rechargeBalance
    );

    return routes;
  }
}
import { Router } from "express";
import { BuyRepositoryImpl } from "../../infrastructure/repository/buy.repositoryimpl";
import { BuyHandlerDatasource } from "../../infrastructure/datasource/buy-handler.datasource";
import { BuyController } from "./buys.controller";
import validatorHandler from "../middlewares/validator.handler";
import { buyGetCodeSchema } from "../../domain/validation/buy.validation";
import authHandler from "../middlewares/auth.handler";

export class BuyRoutes {
  static get routes() {
    const routes = Router();

    const buyRepository = new BuyRepositoryImpl(
      new BuyHandlerDatasource
    )

    const buyController = new BuyController(buyRepository); 

    routes.post(
      '/get-code',
      validatorHandler(buyGetCodeSchema, 'body'),
      buyController.getCode
    )

    routes.get(
      '/confirm/:code',
      authHandler(),
      buyController.confirmCode
    )

    return routes;
  }
}
import { Router } from "express";
import { UserController } from "./users.controller";
import { UserRepositoryImpl } from "../../infrastructure/repository/user.repositoryimpl";
import { UserHandlerDatasource } from "../../infrastructure/datasource/user-handler.datasource";
import validatorHandler from "../middlewares/validator.handler";
import { userSchema } from "../../domain/validation/user.validations";


export class UserRoutes {
  static get routes() {
    const routes = Router();

    const userRepository = new UserRepositoryImpl(
      new UserHandlerDatasource
    )

    const userController = new UserController(userRepository);

    routes.post(
      '/register', 
      validatorHandler(userSchema, 'body'), 
      userController.registerUser
    );

    return routes;
  }
}
import { Request, Response } from "express";
import { UserRepository } from "../../domain/repository/user.repository";
import { CreateUserDto } from "../../domain/dtos/create-user.dto";
import { HTTP_STATUS } from "../../constants/httpStatus";
import { logger } from "../../config/logger";

export class UserController {

  constructor(
    private readonly userRepository: UserRepository,
  ) { }

  public registerUser = async (req: Request, res: Response) => {
    const userDto = new CreateUserDto(req.body)
    logger.info('Register User');
    const result = await this.userRepository.create(userDto);
    if(!result.success){
      logger.error('error registering user', {
        "controller": "users.controller"
      });
      return res
      .status(result?.cod_error || HTTP_STATUS.INTERNAL_SERVER_ERROR)
      .json(result)
    }
    logger.info('User Register Successfully');
    return res
      .status(HTTP_STATUS.OK)
      .json(result)
  }
}
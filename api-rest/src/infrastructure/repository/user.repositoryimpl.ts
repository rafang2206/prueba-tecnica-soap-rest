import { UserDatasource } from "../../domain/datasource/user.datasource";
import { CreateUserDto } from "../../domain/dtos/create-user.dto";
import { UserRepository } from "../../domain/repository/user.repository";
import { DataResponse } from "../../types";


export class UserRepositoryImpl implements UserRepository {

  constructor(private readonly userDatasource: UserDatasource){}

  create(user: CreateUserDto): Promise<DataResponse> {
    return this.userDatasource.create(user);
  }
  
}
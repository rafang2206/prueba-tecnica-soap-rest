import axios from "axios";
import { UserDatasource } from "../../domain/datasource/user.datasource";
import { CreateUserDto } from "../../domain/dtos/create-user.dto";
import { CONFIGURATION } from "../../config/configuration";
import { XMLParser } from "fast-xml-parser";
import { DataResponse } from "../../types";


export class UserHandlerDatasource implements UserDatasource {
  async create(user: CreateUserDto): Promise<DataResponse> {
    try {
      console.log(`realizando peticion a ${CONFIGURATION.SOAP_URL}`)
      const { data } = await axios.post(CONFIGURATION.SOAP_URL + '/user/register', { ...user, phone: +user.phone });
      const parser = new XMLParser();
      let jObj = parser.parse(data);
      const response = jObj['Envelope']['Body']['Response'];
      return { ...response, success: true };
    } catch (error: any) {
      const parser = new XMLParser();
      let jObj = parser.parse(error?.response?.data);
      const response = jObj['Envelope']['Body']['Error'];
      return { ...response, success: false };
    }
  }
  
}
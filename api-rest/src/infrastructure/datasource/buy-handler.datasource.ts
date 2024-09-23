import axios from "axios";
import { BuyDatasource } from "../../domain/datasource/buy.datasource";
import { BuyGetCodeDto } from "../../domain/dtos/buy-get-code.dto";
import { DataResponse } from "../../types";
import { XMLParser } from "fast-xml-parser";
import { CONFIGURATION } from "../../config/configuration";
import { BuyConfirmCodeDto } from "../../domain/dtos/buy-confirm.code.dto";

export class BuyHandlerDatasource implements BuyDatasource {
  async confirmBuy(buyConfirmCodeDto: BuyConfirmCodeDto): Promise<DataResponse> {
    const { code, sessionId } = buyConfirmCodeDto;
    try {
      const { data } = await axios.get(CONFIGURATION.SOAP_URL + `/buy/confirm/${code}`, { 
        headers: {
          Authorization: `Bearer ${sessionId}`
        }
      });
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
  
  async getCode(buyGetCode: BuyGetCodeDto): Promise<DataResponse> {
    const { document, phone } = buyGetCode;
    try {
      const { data } = await axios.post(CONFIGURATION.SOAP_URL + `/buy/get-code`, { document, phone });
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
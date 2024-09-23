import axios from "axios";
import { CONFIGURATION } from "../../config/configuration";
import { WalletDatasource } from "../../domain/datasource/wallet.datasource";
import { XMLParser } from "fast-xml-parser";
import { DataResponse } from "../../types";
import { WalletBalanceDto } from "../../domain/dtos/wallet-balance.dto";
import { WalletRechargeDto } from "../../domain/dtos/wallet-recharge.dto";

export class WalletHandlerDatasource implements WalletDatasource {
  async rechargeBalance(walletRechargeDto: WalletRechargeDto): Promise<DataResponse> {
    const { document, phone, amount } = walletRechargeDto;
    try {
      const { data } = await axios.post(CONFIGURATION.SOAP_URL + `/wallet/recharge-wallet`, { document, phone, amount } );
      const parser = new XMLParser();
      let jObj = parser.parse(data);
      console.log(jObj);
      const response = jObj['Envelope']['Body']['Response'];
      return { ...response, success: true };
    } catch (error: any) {
      const parser = new XMLParser();
      let jObj = parser.parse(error?.response?.data);
      console.log("error", jObj);
      const response = jObj['Envelope']['Body']['Error'];
      return { ...response, success: false };
    }
  }
  async getBalance(walletBalanceDto: WalletBalanceDto): Promise<DataResponse> {
    const { document, phone } = walletBalanceDto;
    try {
      const { data } = await axios.get(CONFIGURATION.SOAP_URL + `/wallet/balance?document=${document}&phone=${+phone}`);
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
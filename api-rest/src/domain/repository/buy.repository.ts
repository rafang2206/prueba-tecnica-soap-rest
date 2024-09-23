import { DataResponse } from "../../types";
import { BuyConfirmCodeDto } from "../dtos/buy-confirm.code.dto";
import { BuyGetCodeDto } from "../dtos/buy-get-code.dto";

export abstract class BuyRepository {
  abstract getCode(buyGetCode: BuyGetCodeDto): Promise<DataResponse>;
  abstract confirmBuy(buyConfirmCodeDto: BuyConfirmCodeDto): Promise<DataResponse>;
}
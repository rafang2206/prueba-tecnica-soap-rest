import { BuyConfirmCodeDto } from "../../domain/dtos/buy-confirm.code.dto";
import { BuyGetCodeDto } from "../../domain/dtos/buy-get-code.dto";
import { BuyRepository } from "../../domain/repository/buy.repository";
import { DataResponse } from "../../types";


export class BuyRepositoryImpl implements BuyRepository {

  constructor(private readonly buyRepository: BuyRepository){}
  confirmBuy(buyConfirmCodeDto: BuyConfirmCodeDto): Promise<DataResponse> {
    return this.buyRepository.confirmBuy(buyConfirmCodeDto);
  }
  getCode(buyGetCode: BuyGetCodeDto): Promise<DataResponse> {
    return this.buyRepository.getCode(buyGetCode);
  }

}
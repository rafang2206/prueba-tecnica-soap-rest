import { Request, Response } from "express";
import { HTTP_STATUS } from "../../constants/httpStatus";
import { BuyRepository } from "../../domain/repository/buy.repository";
import { BuyGetCodeDto } from "../../domain/dtos/buy-get-code.dto";
import { BuyConfirmCodeDto } from "../../domain/dtos/buy-confirm.code.dto";
import { logger } from "../../config/logger";

export class BuyController {

  constructor(
    private readonly buyRepository: BuyRepository,
  ) { }

  public getCode = async (req: Request, res: Response) => {
    const buyDto = new BuyGetCodeDto(req.body)
    logger.info("Request Code for Buy");
    const result = await this.buyRepository.getCode(buyDto);
    if(!result.success){
      logger.error("Error requesting code for Buy", {
        "controller": "buys.controller"
      });
      return res
      .status(result?.cod_error || HTTP_STATUS.INTERNAL_SERVER_ERROR)
      .json(result)
    }
    logger.info("Get Code for Buy Successfully");
    return res
      .status(HTTP_STATUS.OK)
      .json(result)
  }

  public confirmCode = async (req: Request, res: Response) => {
    const { code } = req.params;
    const sessionId = req.sessionId;
    logger.info("Confirm Code for Buy");
    const buyConfirmCodeDto = new BuyConfirmCodeDto({ code, sessionId: String(sessionId) });
    const result = await this.buyRepository.confirmBuy(buyConfirmCodeDto);
    if(!result.success){
      logger.error("Error Confirm Code for Buy", {
        "controller": "buys.controller"
      });
      return res
      .status(result?.cod_error || HTTP_STATUS.INTERNAL_SERVER_ERROR)
      .json(result)
    }
    logger.info("Confirm Code for Buy Successfully");
    return res
      .status(HTTP_STATUS.OK)
      .json(result)
  }
}
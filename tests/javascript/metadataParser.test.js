import metaDataParser from '../../resources/assets/js/utils/metaDataParser'

describe('meta data parser', () => {
    it('separates camel case labels into separate words', () => {
        const sampleData = {
            "ApplicationRecordVersion": "0",
            "TimeCreated": "14:52:21+00:00",
            "PhotometricInterpretation": "RGB"
        }

        const parsedData = metaDataParser(sampleData)

        expect(parsedData).deep.to.equal({
            "Application Record Version": "0",
            "Time Created": "14:52:21+00:00",
            "Photometric Interpretation": "RGB"
        })
    })
})
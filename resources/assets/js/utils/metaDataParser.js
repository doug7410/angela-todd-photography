export default function (data) {
    Object.keys(data).forEach(label => {

        const newLabel = label.replace(/([A-Z])/g, ' $1').replace(/^ /, '')

        data[newLabel] = data[label]

        delete data[label]
    })

    return data
}